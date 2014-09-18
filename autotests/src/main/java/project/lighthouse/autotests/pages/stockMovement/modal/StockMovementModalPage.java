package project.lighthouse.autotests.pages.stockMovement.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.DateInput;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.ProductAutoComplete;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public abstract class StockMovementModalPage extends ModalWindowPage {

    public StockMovementModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("date", new DateInput(this, "//*[@name='date']"));
        put("store", new SelectByVisibleText(this, "//*[@name='store']"));
        put("product.name", new ProductAutoComplete(this, "//*[@name='product.name' and contains(@class, 'input')]"));
        put("price", new Input(this, "//*[@name='price']"));
        put("quantity", new Input(this, "//*[@name='quantity']"));
    }

    public abstract AbstractObjectCollection getProductCollection();

    public String getTotalSum() {
        String xpath = String.format("%s//*[@class='totalSum']", modalWindowXpath());
        return findVisibleElement(By.xpath(xpath)).getText();
    }

    public abstract void addProductButtonClick();

    public abstract Integer getProductRowsCount();

    @Override
    public void deleteButtonClick() {
        String xpath = String.format("%s//*[@class='removeLink']", modalWindowXpath());
        findVisibleElement(By.xpath(xpath)).click();
    }

    protected void confirmationOkClick(String buttonLabel) {
        new PrimaryBtnFacade(this, buttonLabel).click();
    }

    protected void deleteButtonClick(String cssClass) {
        String xpath = String.format("%s//*[@class='removeLink %s']", modalWindowXpath(), cssClass);
        findVisibleElement(By.xpath(xpath)).click();
    }

    protected void confirmDeleteButtonClick(String cssClass) {
        String xpath = String.format("%s//*[@class='confirmLink__confirmation']//*[@class='removeLink %s']", modalWindowXpath(), cssClass);
        findVisibleElement(By.xpath(xpath)).click();
    }

    protected void addProductButtonClick(String cssClass) {
        String xpath = String.format("%s//*[contains(@class, '%s')]", modalWindowXpath(), cssClass);
        findVisibleElement(By.xpath(xpath)).click();
    }

    protected Integer getProductRowsCount(String tableClass) {
        String cssSelector = String.format("table.%s tbody>tr", tableClass);
        return getDriver().findElements(By.cssSelector(cssSelector)).size();
    }
}
