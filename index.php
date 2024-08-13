<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db.php';
include_once 'Post.php';

$database = new Database();
$db = $database->getConnection();

$post = new Post($db);

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Check if the API version is included and valid
if ($request[0] !== 'api' || $request[1] !== 'v1') {
    http_response_code(404);
    echo json_encode(["message" => "API version not found."]);
    exit;
}

// Shift off 'api' and 'v1' from the request array to get the actual resource
array_shift($request); // remove 'api'
array_shift($request); // remove 'v1'

switch ($method) {
    case 'POST':
        if ($request[0] === 'posts') {
            $data = json_decode(file_get_contents("php://input"));


            // Define an array to hold error messages
            $errors = [];

            // Check each field and add a message to the errors array if it's empty
            if (empty($data->title)) {
                $errors[] = "Title field is required";
            }
            if (empty($data->content)) {
                $errors[] = "Content field is required";
            }
            if (empty($data->author)) {
                $errors[] = "Author field is required";
            }

            // If there are errors, return them as JSON
            if (!empty($errors)) {
                echo json_encode(["messages" => $errors]);
                http_response_code(400); // Bad Request
                exit();
            }

            // Set the post properties
            $post->title = $data->title;
            $post->content = $data->content;
            $post->author = $data->author;
            $postData = $post->create();

            // Attempt to create the post
            if ($postData) {
                http_response_code(201); // Created
                echo json_encode(["message" => "Post created successfully.", "data" => $postData]);
            } else {
                http_response_code(503); // Service Unavailable
                echo json_encode(["message" => "Unable to create post."]);
            }
        }
        break;

    case 'GET':
        if ($request[0] === 'posts') {
            if (isset($request[1])) {
                $post->id = $request[1];
                if ($post->readOne()) {
                    echo json_encode([
                        "id" => $post->id,
                        "title" => $post->title,
                        "content" => $post->content,
                        "author" => $post->author,
                        "created_at" => $post->created_at,
                        "updated_at" => $post->updated_at
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Post not found."]);
                }
            } else {
                $stmt = $post->readAll();
                $posts_arr = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $post_item = [
                        "id" => $id,
                        "title" => $title,
                        "content" => $content,
                        "author" => $author,
                        "created_at" => $created_at,
                        "updated_at" => $updated_at
                    ];
                    array_push($posts_arr, $post_item);
                }

                echo json_encode($posts_arr);
            }
        }
        break;

    case 'PUT':
        if ($request[0] === 'posts' && isset($request[1])) {
            $data = json_decode(file_get_contents("php://input"));
            $post->id = $request[1];

            if ($post->readOne()) {
                if (!empty($data->title)) $post->title = $data->title;
                if (!empty($data->content)) $post->content = $data->content;
                if (!empty($data->author)) $post->author = $data->author;
                $updatedPostData = $post->update();
                if ($updatedPostData) {
                    echo json_encode(["message" => "Post updated successfully.", 'data' => $updatedPostData]);
                } else {
                    http_response_code(503);
                    echo json_encode(["message" => "Unable to update post."]);
                }
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Post not found."]);
            }
        }
        break;

    case 'DELETE':
        if ($request[0] === 'posts' && isset($request[1])) {
            $post->id = $request[1];

            if ($post->readOne()) {
                if ($post->delete()) {
                    echo json_encode(["message" => "Post deleted successfully."]);
                } else {

                    http_response_code(503);
                    echo json_encode(["message" => "Unable to delete post."]);
                }
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Post not found."]);
            }
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed."]);
        break;
}
