package project.lighthouse.autotests.pages.commercialManager.product;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

public class ProductCardView extends ProductCreatePage {

    public ProductCardView(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String elementName, String expectedValue) {
        commonActions.checkElementValue("", elementName, expectedValue);
    }

    public void checkCardValue(ExamplesTable checkValuesTable) {
        commonActions.checkElementValue("", checkValuesTable);
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
