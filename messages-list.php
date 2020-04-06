<style>
    .messages-list-title {
        line-height: 1.25;
    }

    .messages-list {
        overflow-x: auto;
    }

    .messages-list th,
    .messages-list td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .messages-list table {
        width: 95%;
        border-collapse: collapse;
    }

    .messages-list th {
        background-color: #23282d;
        color: #eee;
    }

    .messages-list td {
        vertical-align: middle;
        min-width: 125px;
    }
</style>

<h1 class="messages-list-title">Сообщения, оставленные пользователями</h1>
<div class="messages-list">
    <table>
        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th>Тема</th>
            <th>Сообщение</th>
        </tr>
        <?php echo $layout ?>
    </table>
</div>