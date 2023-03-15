<?php

namespace System\fake;

class Fake
{
    private static array $firstName = ['Adam', 'Alex', 'Aaron', 'Ben', 'Carl', 'Dan', 'David', 'Edward', 'Fred', 'Frank', 'George', 'Hal', 'Hank', 'Ike', 'John', 'Jack', 'Joe', 'Larry', 'Monte', 'Matthew', 'Mark', 'Nathan', 'Otto', 'Paul', 'Peter', 'Roger', 'Roger', 'Steve', 'Thomas', 'Tim', 'Ty', 'Victor', 'Walter', 'William'];
    private static array $lastName = ['Smith', 'Johnson', 'Brown', 'Taylor', 'Clark', 'Lee', 'Allen', 'Scott', 'Young', 'Walker', 'Wright', 'Perez', 'Green', 'Lewis', 'White', 'Jones', 'Jackson', 'Harris', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Martinez', 'Anderson', 'Wilson', 'Thomas', 'Moore', 'Martin', 'Jackson', 'Lee', 'Hall', 'Adams', 'Nelson', 'Mitchell', 'Campbell', 'Robinson', 'King', 'Carter', 'Turner', 'Phillips', 'Cooper', 'Gonzalez', 'Parker', 'Evans', 'Edwards', 'Collins', 'Stewart', 'Morris', 'Murphy', 'Reed'];
    private static array $username = ["mysticmango", "lunarlynx", "neonninja", "radiantraven", "starrysky", "electricemu", "cosmiccactus", "sapphiresloth", "turboturtle", "aquaxolotl", "galacticgiraffe", "crimsoncoyote", "solarsquirrel", "goldengazelle", "thunderoustoucan", "amberantelope", "frostyfox", "diamonddragonfly", "junglejaguar", "celestialchameleon", "rusticraccoon", "oceanicotter", "sereneswan", "fireflyfalcon", "gildedgorilla", "arcticarmadillo", "mythicalmoth", "electriceagle", "cosmiccrab", "silverseahorse", "radiantrabbit", "turquoisetiger", "tropicaltortoise", "lunarllama", "sapphireserpent", "frostyferret", "amberalpaca", "galacticgecko", "crimsoncaterpillar", "solarsalmon", "goldenguppy", "thunderoustapir", "diamonddolphin", "junglejay", "celestialcentipede", "rusticrhino", "oceanicoctopus", "serenestork", "fireflyflamingo", "gildedgull"];
    private static array $domainEmail = ["outlook.com", "hotmail.com", "gmail.com", "yahoo.com"];

    public static function fullName(): string
    {
        return self::name() . " " . self::family();
    }

    public static function family(): string
    {
        return self::$lastName[array_rand(self::$lastName)];
    }

    public static function name(): string
    {
        return self::$firstName[array_rand(self::$firstName)];
    }

    public static function username(): string
    {
        return self::$username[array_rand(self::$username)];
    }

    public static function email(): string
    {
        return self::$username[array_rand(self::$username)] . "@" . self::$domainEmail[array_rand(self::$domainEmail)];
    }

    public static function password(): string
    {
        return '!#$%&()*+-/;<=>?@[]_{|}~';
    }
}