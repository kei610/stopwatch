<?php

include_once '../config/function.php';

// 要件
// タスクの追加５件まで 一番古いやつから表示 flagが0
// 完了したらタスクの表示を消す

// get送信＝idからタスクを取る
$tasks = getTask();

if(!empty($_GET)){

    debug('GET通信があります');

    $id = filter_input(INPUT_GET,'id');

    $result = getTaskTitle($id);

    $task_title = $result['name'];

}

// リストの項目をクリックすると、get送信されてタイトルの部分に表示される

// post送信
if(!empty($_POST)){

    debug('POST送信があります');

    $task = filter_input(INPUT_POST,'name');

    debug($task);

    $result = validTaskDup($task);

    if(empty($result)){
    
        debug('タスク重複チェックOK');
        
        addTask($task);

        $tasks = getTask();

        debug('タスクを追加しました');
    } else{

        debug('すでに登録されています');
    }
}

if(!empty($_POST['complete'])){

}

?>

<?php include_once '../templete/head.php';?>
<body>
    <?php include_once '../templete/header.php';?>

    <div class="main">
        <div class="container">
            <section class="main_contents">
                <div class="task_area">
                    <h2 class="task_title"><?php if(!empty($_GET)) echo $task_title; ?></h2>
                </div>
                <div class="time_area" id="timerLabel">
                    <span>00</span>:<span>00</span>:<span>00</span>
                </div>
                <div class="btn_area">
                    <button class="btn-complete" type="submit" name="complete" value="complete" formmethod="$_POST">完了</button>
                    <button class="btn-reset" onclick="reset()" value="RESET" id="resetBtn">リセット</button>
                    <button class="btn-stop" onclick="stop()" value="STOP" id="stopBtn">停止</button>
                    <button class="btn-start" onclick="start()" value="START" id="startBtn">開始</button>
                </div>
            </section>
            <section class="sidebar">
                <ol class="task_list">
                    <?php if(!empty($tasks)):?>
                        <?php foreach($tasks as $task):?>
                            <li class="task_item">
                                <a href="?id=<?= $task['id']; ?>" class="task_link">
                                 <?= $task['name']; ?>
                                </a>
                            </li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ol>
                <form action="" method="POST" class="form">
                    <!-- <label class="form_item">
                        <p>制限時間</p>
                        <input type="text" name="" class="input_time">
                    </label> -->
                    <label class="form_item">
                        <p>タスク</p>
                        <input type="text" name="name" class="input_task">
                    </label>
                    <div class="btn_container">
                        <input type="submit" value="＋" class="submit">
                    </div>
                    </form>
            </section>
        </div>
    </div>
    <footer class="footer">

    </footer>

    <script src="js/stopwatch.js"></script>
</body>
</html>