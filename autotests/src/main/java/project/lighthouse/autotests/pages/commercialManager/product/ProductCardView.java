package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

public class ProductCardView extends ProductCreatePage {

    @FindBy(xpath = "//*[@class='user__editLink']")
    @SuppressWarnings("unused")
    WebElement editButtonWebElement;

    @FindBy(xpath = "//*[normalize-space(text())='Изменить наценку/цену']")
    @SuppressWarnings("unused")
    WebElement editProductMarkUpAndPriceButtonWebElement;

    public ProductCardView(WebDriver driver) {
        super(driver);
    }

    public void editButtonClick() {
        findVisibleElement(editButtonWebElement).click();
    }

    public void editProductMarkUpAndPriceButtonClick() {
        findVisibleElement(editProductMarkUpAndPriceButtonWebElement).click();
    }
}
