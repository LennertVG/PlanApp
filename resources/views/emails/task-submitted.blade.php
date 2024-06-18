<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taak ingediend</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #666666;
            font-size: 16px;
            line-height: 1.5;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .button-container a {
            text-decoration: none;
            background-color: #6254e8;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .button-container a:hover {
            background-color: #5145c8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Taak ingediend</h1>
        <p>Beste meneer/mevrouw {{ $task->course->users()->first()->name }}</p>
        <p>De taak "{{ $task->name }}" is ingediend door: {{ $user->firstname }} {{ $user->name }} uit groep {{ $grade }}{{ $class }}.</p>
        <p>Ingediend op: {{ $task->users()->wherePivot('user_id', $user->id)->first()->pivot->submitted_at }}</p>
    </div>
</body>
</html>
