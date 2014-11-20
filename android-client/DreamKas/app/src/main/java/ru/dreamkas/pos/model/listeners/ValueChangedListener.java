package ru.dreamkas.pos.model.listeners;

public interface ValueChangedListener<T> {
    public void changedTo(T newValue);
}
