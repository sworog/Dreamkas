package ru.dreamkas.pageObjects.elements;

import ru.dreamkas.pageObjects.CommonPageObject;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebElement;

public class ConfirmationElement extends CommonPageObject {

    @FindBy(id = "android:id/button1")
    private WebElement confirmButton;

    public void clickOnConfirmButton() {
        confirmButton.click();
    }
}
