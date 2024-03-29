[« На главную](../../README.md)

---

## Микросервис Авторизация пользователя

### Цель:
В предыдущем ДЗ необходимо было реализовать Endpoint на Игровом сервере для приема входящих сообщений от Агента.

Термины:
- Игровой сервер - это приложение, на котором вычисляется состояние танковых боев, то есть выполняются все те команды, которые рассматривались на предыдущих занятиях.
- Агент - это специальное приложение, на котором игрок запускает свой алгоритм управления своей командой танков.

При реализации этого Endpoint возникает задача авторизации пользователя на отправку сообщений для управления командой танков в конкретной игре, чтобы не допустить вмешательства в процесс игры сторонними пользователями.
В результате выполнения этого ДЗ будет разработан микросервис, который выдает jwt токен из участников танкового сражения, для того, чтобы игровой сервер мог принять решение о возможности выполнения входящего сообщения от имени пользователя.

Цель: применить навыки разработки микросервиса.

### Описание/Пошаговая инструкция выполнения домашнего задания:
Предполагается реализация микросервиса авторизации с помощью jwt токена.
Алгоритм взаимодействия сервиса авторизации и Игрового сервера следующий:

- Один из пользователей организует танковый бой и определяет список участников (их может быть больше 2-х). На сервер авторизации уходит запрос о том, что организуется танковый бой и присылается список его участников. Сервер в ответ возвращает id танкового боя.
- Аутентифицированный пользователь посылает запрос на выдачу jwt токена, который авторизует право этого пользователя на участие в танковом бое. Для этого он должен указать в запросе id танкового боя. Если пользователь был указан в списке участников танкового боя, то он выдает пользователю jwt токен, в котором указан id игры.
- Пользователь при отправке сообщений в Игровой сервер прикрепляет к сообщениям выданный jwt токен, а сервер при получении сообщения удостоверяется, что токен был выдан сервером авторизации (проверят хэш jwt токена) и проверяет, что пользователь запросил выполнение операции в игре, в которой он эту операцию может выполнять.

### Критерии оценки:
1. Задача сдана на проверку - 1 балл.
2. Код компилируется без ошибок, тесты выполняются успешно. - 1 балл
3. Настроен CI, по которому можно определить успешность п. 2 - 2 балла
4. Реализована аутентификация с помощью jwt (выдачу токенов выполняет сервис авторизации) - 2 балла
5. Реализован запрос на сервис авторизации для создания нового танкового боя - 2 балла
6. Реализована проверка jwt во входящем сообщении на Игровом сервере - 2 балла
