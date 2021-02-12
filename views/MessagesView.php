<!DOCTYPE html>
<html>

<head>
    <?php require 'includes/head.php' ?>
</head>

<body>
    <?php require 'includes/menu.php' ?>
    <div class="py-5 h-100 align-items-center d-flex flex-column" id="main">
        <?php if (!isset($error)) : ?>
            <div class="py-2 d-flex flex-column bg-light" id="messagges">

                <?php foreach ($data as $message) : ?>


                    <div class=" <?php if ($message["user_from"] == $_SESSION["id"]) : echo "message message-to";
                                    else : echo "message message-from";
                                    endif; ?>">

                        <p class="text-dark rounded"><?php echo $message["content"] ?></p>
                        <span class="bg-secondary col-4 small p-1 text-center d-inline-block"><?php echo $message["date"] ?></span>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="py-5 w-80">
            <form action="?controller=messages&action=messageswith&to=<?php echo $_GET["to"]?>" method="POST">
                <textarea class="form-control" name="message" id="message" cols="30" rows="4"></textarea>
                <input type="submit" name="submit" class="form-control btn btn-primary mt-2">
            </form>
        </div>
    </div>
</body>

</html>