package ru.dreamkas.common.item.interfaces;

import ru.dreamkas.handler.field.FieldErrorChecker;

public interface FieldErrorCheckable extends CommonItemType {

    public FieldErrorChecker getFieldErrorMessageChecker();
}
