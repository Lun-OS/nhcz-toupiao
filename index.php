<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>校花校草评选</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        .col {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 10px;
        }
        @media (max-width: 768px) {
            .col {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        .candidate {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .candidate img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .carousel {
            position: relative;
            overflow: hidden;
        }
        .carousel img {
            display: none;
        }
        .carousel img.active {
            display: block;
        }
        .carousel-controls {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .carousel-control {
            cursor: pointer;
            padding: 5px;
            background-color: #ddd;
            border-radius: 50%;
            margin: 0 5px;
        }
        .carousel-control:hover {
            background-color: #ccc;
        }
        .candidate h3 {
            margin: 10px 0;
            font-size: 1.5em;
        }
        .candidate p {
            margin: 5px 0;
            color: #666;
        }
        .candidate button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1em;
        }
        .candidate button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">校花校草评选</h1>
        <div class="row">
            <?php
            $data = file('data.ini', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $candidates = array();
            foreach ($data as $line) {
                $line = trim($line, '[]');
                $candidate = array();
                $parts = explode(',', $line);
                foreach ($parts as $part) {
                    list($key, $value) = explode('=', $part);
                    $key = trim($key, '"');
                    $value = trim($value, '"');
                    $candidate[$key] = $value;
                }
                $candidates[] = $candidate;
            }

            // 按票数降序排序
            usort($candidates, function($a, $b) {
                return (int)$b['votes'] - (int)$a['votes'];
            });

            foreach ($candidates as $candidate) {
                $imgDir = $candidate['img'];
                if (is_dir($imgDir)) {
                    $imgFiles = scandir($imgDir);
                    $imgCount = count($imgFiles) - 2; // 减去'.'和'..'
                } else {
                    $imgCount = 0;
                }

                echo '<div class="col">';
                echo '<div class="candidate">';
                echo '<div class="carousel">';
                for ($i = 1; $i <= $imgCount; $i++) {
                    $imgSrc = $imgDir . 'img' . $i . '.jpg';
                    $active = ($i == 1) ? 'active' : '';
                    echo '<img src="' . $imgSrc . '" class="carousel-item ' . $active . '" alt="Photo">';
                }
                echo '</div>';
                if ($imgCount > 1) {
                    echo '<div class="carousel-controls">';
                    for ($i = 1; $i <= $imgCount; $i++) {
                        echo '<span class="carousel-control" data-slide="' . $i . '"></span>';
                    }
                    echo '</div>';
                }
                echo '<h3>' . $candidate['name'] . '</h3>';
                echo '<p>班级：' . $candidate['class'] . '</p>';
                echo '<p>票数：' . $candidate['votes'] . '</p>';
                echo '<button onclick="vote(' . $candidate['id'] . ')">投票</button>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousels = document.querySelectorAll('.carousel');
            const controls = document.querySelectorAll('.carousel-control');

            carousels.forEach(carousel => {
                let currentSlide = 1;
                const items = carousel.querySelectorAll('.carousel-item');
                const totalSlides = items.length;

                function showSlide(slide) {
                    items.forEach(item => item.classList.remove('active'));
                    items[slide - 1].classList.add('active');
                    currentSlide = slide;
                }

                function nextSlide() {
                    let nextSlide = currentSlide % totalSlides + 1;
                    showSlide(nextSlide);
                }

                // 自动轮播
                let interval = setInterval(nextSlide, 3000);

                // 手动控制
                controls.forEach(control => {
                    control.addEventListener('click', function() {
                        const slide = this.dataset.slide;
                        showSlide(slide);
                        clearInterval(interval);
                        interval = setInterval(nextSlide, 3000);
                    });
                });

                // 鼠标悬停时停止轮播
                carousel.addEventListener('mouseover', () => clearInterval(interval));
                carousel.addEventListener('mouseout', () => interval = setInterval(nextSlide, 3000));
            });
        });

        function vote(id) {
            fetch('vote.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    window.location.reload();
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <style>
        .add-btn{
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: #007bff;
            color: #fff;
            font-size: 24px;
            line-height: 50px;
            text-align: center;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="add-btn" onclick="window.location.href='add.php'">+</div>
</body>
</html>