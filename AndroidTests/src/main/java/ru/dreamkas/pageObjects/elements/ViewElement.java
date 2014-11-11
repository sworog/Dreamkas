package ru.dreamkas.pageObjects.elements;

import org.openqa.selenium.By;

import ru.dreamkas.pageObjects.CommonPageObject;

public class ViewElement {

    private CommonPageObject commonPageObject;
    private By findBy;

    public ViewElement(CommonPageObject commonPageObject, String id) {
        this.commonPageObject = commonPageObject;
        findBy = By.id("ru.dreamkas.pos.debug:id/" + id);
    }

    public CommonPageObject getCommonPageObject() {
        return commonPageObject;
    }

    public By getFindBy() {
        return findBy;
    }
}
