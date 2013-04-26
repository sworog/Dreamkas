package project.lighthouse.autotests.pages.product;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.Map;

public class ProductCardView extends ProductCreatePage {

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

    public ProductCardView(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String elementName, String expectedValue) {
        WebElement element = items.get(elementName).getWebElement();
        commonPage.shouldContainsText(elementName, element, expectedValue);
    }

    public void checkCardValue(ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkCardValue(elementName, expectedValue);
        }
    }

    public void editButtonClick() {
        $(editButton).click();
    }
}
