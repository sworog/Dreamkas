package project.lighthouse.autotests.pages.commercialManager.product;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ProductCardView extends ProductCreatePage {

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

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
        $(editButton).click();
    }
}
