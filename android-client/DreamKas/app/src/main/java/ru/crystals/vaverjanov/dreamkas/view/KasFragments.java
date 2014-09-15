package ru.crystals.vaverjanov.dreamkas.view;

public enum KasFragments
{
    Kas(0), Store(1), Exit(2);

    private final int code;
    KasFragments(int code) { this.code = code; }
    public int getValue() { return code; }
}
