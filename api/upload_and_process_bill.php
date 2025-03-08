<?php
header('Content-Type: application/json');
require("config/session.php");
require_once("config/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['bill'])) {
        $file = $_FILES['bill'];
        $uploadDir = './uploads/';
        $uploadFile = $uploadDir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // 文件上传成功，现在处理图片
            $result = processImage($uploadFile);
            
            if ($result && is_array($result) && !empty($result)) {
                // 返回处理结果，而不是立即保存到数据库
                echo json_encode([
                    'success' => true, 
                    'message' => '账单处理成功',
                    'data' => $result
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => '账单处理失败或返回数据为空']);
            }
            
        } else {
            echo json_encode(['success' => false, 'message' => '文件上传失败']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '没有收到文件']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求方法']);
}

$conn->close();

function processImage($imagePath) {
    $imageData = base64_encode(file_get_contents($imagePath));
    $apiKey = OPEN_AI_KEY;
    $url = 'https://api.openai.com/v1/chat/completions';
    
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    $data = [
        'model' => 'gpt-4o',
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => '这是一张账单图片。请提取日期(YYYY-MM-DD 格式)、金额、描述和类别(餐饮/购物/交通/娱乐/其他)信息。以JSON格式返回 不要有Markdown语法，包含date、amount、description和category字段。'
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => 'data:image/jpeg;base64,' . $imageData
                        ]
                    ]
                ]
            ]
        ],
        'max_tokens' => 300
    ];
    
    // 发送请求到 OpenAI API
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        error_log("cURL Error: " . $err);
        return null;
    }
    
    // 解析 API 响应
    $result = json_decode($response, true);
    if (isset($result['choices'][0]['message']['content'])) {
        $content = $result['choices'][0]['message']['content'];
        
        // 移除所有的 ``` 字符
        $content = str_replace('```', '', $content);
        
        // 尝试解析 JSON
        $expenses = json_decode($content, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            // JSON 解析成功
            return $expenses;
        } else {
            // JSON 解析失败，记录错误
            error_log('JSON 解析错误: ' . json_last_error_msg());
            error_log('原始内容: ' . $content);
            return null;
        }
    } else {
        error_log('API 响应中没有找到预期的内容');
        error_log('API 响应: ' . print_r($result, true));
        return null;
    }

    return null;
    
    // return [
    //     'date' => date('Y-m-d'),
    //     'amount' => 100.00,
    //     'description' => 'AI 处理的账单',
    //     'category' => '其他'
    // ];
}
?>