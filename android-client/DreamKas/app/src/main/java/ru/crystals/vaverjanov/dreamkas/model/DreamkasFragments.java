package ru.crystals.vaverjanov.dreamkas.model;

public enum DreamkasFragments
{
    Kas(0), Store(1), Exit(2);

    private final int code;
    DreamkasFragments(int code) { this.code = code; }
    public int getValue() { return code; }
}
