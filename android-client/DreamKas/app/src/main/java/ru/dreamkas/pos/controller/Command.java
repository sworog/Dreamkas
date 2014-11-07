package ru.dreamkas.pos.controller;

public interface Command<T>{
    public void execute(T data);
}
