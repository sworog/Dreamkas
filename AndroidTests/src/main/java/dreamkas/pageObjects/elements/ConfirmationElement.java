package dreamkas.pageObjects.elements;

import dreamkas.pageObjects.CommonPageObject;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebElement;

public class ConfirmationElement extends CommonPageObject {

    @FindBy(id = "android:id/button1")
    private WebElement confirmButton;

    public void clickOnConfirmButton() {
        confirmButton.click();
    }
}
