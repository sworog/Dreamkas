package project.lighthouse.autotests.common.item.interfaces;

import project.lighthouse.autotests.handler.field.FieldChecker;

public interface FieldCheckable extends CommonItemType {

    public FieldChecker getFieldChecker();
}
