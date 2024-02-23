[« На главную](../../README.md)

---

## Механизм обработки исключений в игре "Космическая битва"

### Цель:
Научится писать различные стратегии обработки исключений так, чтобы соответствующий блок try-catсh не приходилось модифицировать каждый раз, когда возникает потребность в обработке исключительной ситуации по-новому.

### Описание/Пошаговая инструкция выполнения домашнего задания:
Предположим, что все команды находятся в некоторой очереди. Обработка очереди заключается в чтении очередной команды и головы очереди и вызова метода Execute извлеченной команды. Метод Execute() может выбросить любое произвольное исключение.

1. Обернуть вызов Команды в блок try-catch.
2. Обработчик catch должен перехватывать только самое базовое исключение.
3. Есть множество различных обработчиков исключений. Выбор подходящего обработчика исключения делается на основе экземпляра перехваченного исключения и команды, которая выбросила исключение.
4. Реализовать Команду, которая записывает информацию о выброшенном исключении в лог.
5. Реализовать обработчик исключения, который ставит Команду, пишущую в лог в очередь Команд.
6. Реализовать Команду, которая повторяет Команду, выбросившую исключение.
7. Реализовать обработчик исключения, который ставит в очередь Команду - повторитель команды, выбросившей исключение.
8. С помощью Команд из пункта 4 и пункта 6 реализовать следующую обработку исключений:
при первом выбросе исключения повторить команду, при повторном выбросе исключения записать информацию в лог.
9. Реализовать стратегию обработки исключения - повторить два раза, потом записать в лог. Указание: создать новую команду, точно такую же как в пункте 6. Тип этой команды будет показывать, что Команду не удалось выполнить два раза.

### Критерии оценки:
- ДЗ сдано на оценку - 2 балла
- Реализованы пункты 4-7. - 2 балла.
- Написаны тесты к пункту 4-7. - 2 балла
- Реализован пункт 8. - 1 балл
- Написаны тесты к пункту 8. - 1 балл
- Реализован пункт 9. - 1 балл
- Написаны тесты к пункту 9. - 1 балл