package project.lighthouse.autotests.pages.departmentManager.invoice.deprecated;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.objects.web.invoice.InvoiceProductsCollection;

public class InvoiceBrowsing extends InvoiceCreatePage {

    public static final String ITEM_NAME = "invoiceProduct";
    private static final String ITEM_SKU_NAME = "productSku";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    @FindBy(xpath = "//*[@class='addMoreProduct']")
    private WebElement addOneMoreProductLink;

    @FindBy(xpath = "//*[contains(@class, 'dataInputSave')]")
    public WebElement acceptChangesButton;

    @FindBy(xpath = "//*[contains(@class, 'dataInputCancel')]")
    public WebElement discardChangesButton;

    @FindBy(xpath = "//*[contains(@class, 'removeConfirmButton')]")
    public WebElement acceptDeleteButton;

    @FindBy(xpath = "//*[contains(@class, 'removeCancel')]")
    public WebElement discardDeleteButton;

    public String deleteButtonXpath = "//*[@class='invoice__removeLink']";

    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void editButtonClick() {
        findVisibleElement(
                By.xpath("//*[@class='page__controlsLink invoice__editLink']"));
        evaluateJavascript("document.getElementsByClassName('page__controlsLink invoice__editLink')[0].click();");
    }

    public void goToTheaAdditionOfProductsLinkClick() {
        new ButtonFacade(this, "Сохранить и перейти к добавлению товаров").click();
        new PreLoader(getDriver()).await();
    }

    public void addOneMoreProductLinkClick() {
        addNewInvoiceProductButtonClick();
    }

    public void listItemClick(String value) {
        commonViewInterface.itemClick(value);
    }

    public void listItemCheck(String value) {
        commonViewInterface.itemCheck(value);
    }

    public void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable) {
        commonViewInterface.checkListItemWithSkuHasExpectedValue(value, checkValuesTable);
    }

    public void elementClick(String elementName) {
        getItems().get(elementName).getWebElement().click();
    }

    public void acceptChangesButtonClick() throws InterruptedException {
        $(acceptChangesButton).click();
        Thread.sleep(1000);
    }

    public void discardChangesButtonClick() {
        $(discardChangesButton).click();
    }

    public void acceptDeleteButtonClick() throws InterruptedException {
        $(acceptDeleteButton).click();
        Thread.sleep(1000);
    }

    public void discardDeleteButtonClick() {
        $(discardDeleteButton).click();
    }

    public void writeOffStopEditButtonClick() {
        new ButtonFacade(this, "Завершить редактирование").click();
    }

    public void writeOffStopEditlinkClick() {
        findVisibleElement(
                By.xpath("//*[@class='page__controlsLink invoice__stopEditLink']"));
        evaluateJavascript("document.getElementsByClassName('page__controlsLink invoice__stopEditLink')[0].click();");
    }

    public void childrenElementClick(String elementName, String elementClassName) {
        commonViewInterface.childrenItemClickByClassName(elementName, elementClassName);
    }

    public void childrenItemNavigateAndClickByFindByLocator(String elementName) {
        commonViewInterface.childrenItemNavigateAndClickByFindByLocator(elementName, By.xpath(deleteButtonXpath));
    }

    @Deprecated
    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        By findBy = getItems().get(parentElementName).getFindBy();
        commonViewInterface.childrenItemClickByFindByLocator(elementName, findBy);
    }

    public void addNewInvoiceProductButtonClick() {
        new ButtonFacade(this, "Добавить товар").click();
        new PreLoader(getDriver()).await();
    }

    public void checkItemIsNotPresent(String elementName) {
        commonViewInterface.itemCheckIsNotPresent(elementName);
    }

    public InvoiceProductsCollection getInvoiceProductsCollection() {
        return new InvoiceProductsCollection(getDriver(), By.name("invoiceProduct"));
    }
}
