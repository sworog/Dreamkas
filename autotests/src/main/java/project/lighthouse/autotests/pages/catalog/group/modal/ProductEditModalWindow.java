package project.lighthouse.autotests.pages.catalog.group.modal;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Edit product modal window
 */
public class ProductEditModalWindow extends ProductCreateModalWindow {

    @FindBy(xpath = "//*[@id='modal-productEdit']//*[contains(@class, 'product__markup')]")
    private WebElement markUpValueWebElement;

    public ProductEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal-productEdit']";
    }

    protected WebElement findDeleteButton() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink product__removeLink']"));
    }
    public void deleteButtonClick() {
        findDeleteButton().click();
    }

    public String getDeleteButtonText() {
        return findDeleteButton().getText();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink product__removeLink']")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }

    public WebElement getMarkUpValueWebElement() {
        return markUpValueWebElement;
    }
}
