Запустить проект
```
docker-compose -f docker-compose.auction.yml -d
```

Дождаться, когда всё прочихается, поднимутся сервисы, установятся соединения,
запустся процессы

Front
```
http://auction.localhost/
```

RabbitMQ
```
http://rabbit.localhost/
guest:guest
```

Добавить ставку
```
docker exec -it auction-app_auction-app_1 php yii auction/bet 1 1
```

Добавить несколько ставок
```
docker exec -it auction-app_auction-app_1 php yii auction/bets 1 1 100
```