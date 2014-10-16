package ru.dreamkas.common.item.interfaces;

import ru.dreamkas.handler.field.FieldChecker;

public interface FieldCheckable extends CommonItemType {

    public FieldChecker getFieldChecker();
}
