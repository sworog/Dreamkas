package ru.crystals.vaverjanov.dreamkas.controller;

public interface Command<T>
{
    public void execute(T data);
}
