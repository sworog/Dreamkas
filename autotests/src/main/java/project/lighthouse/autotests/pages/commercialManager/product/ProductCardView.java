package project.lighthouse.autotests.pages.commercialManager.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

public class ProductCardView extends ProductCreatePage {

    public ProductCardView(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String elementName, String expectedValue) {
        commonActions.checkElementValue("", elementName, expectedValue);
    }

    public void editButtonClick() {
        String editButtonXpath = "//*[@class='user__editLink']";
        findVisibleElement(By.xpath(editButtonXpath)).click();
    }

    public void editProductButtonClick() {
        findVisibleElement(
                By.xpath("//*[normalize-space(text())='Изменить наценку/цену']")
        ).click();
    }
}
