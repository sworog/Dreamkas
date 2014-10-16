package ru.dreamkas.common.pageObjects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

public abstract class BootstrapPageObject extends CommonPageObject {

    public BootstrapPageObject(WebDriver driver) {
        super(driver);
    }

    public abstract void addObjectButtonClick();

    public String getTitle() {
        return findVisibleElement(By.className("page__title")).getText();
    }
}
