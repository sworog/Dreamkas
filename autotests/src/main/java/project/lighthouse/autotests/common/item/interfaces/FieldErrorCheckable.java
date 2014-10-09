package project.lighthouse.autotests.common.item.interfaces;

import project.lighthouse.autotests.handler.field.FieldErrorChecker;

public interface FieldErrorCheckable extends CommonItemType {

    public FieldErrorChecker getFieldErrorMessageChecker();
}
