package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonView;

public class InvoiceBrowsing extends InvoiceCreatePage {

    public static final String ITEM_NAME = "invoiceProduct";
    private static final String ITEM_SKU_NAME = "productSku";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    @FindBy(xpath = "//*[@class='addMoreProduct']")
    private WebElement addOneMoreProductLink;

    @FindBy(xpath = "//*[@class='page__controlsLink invoice__editLink']")
    public WebElement editButtonLink;

    @FindBy(xpath = "//*[@class='page__controlsLink invoice__stopEditLink']")
    public WebElement invoiceStopEditLink;

    @FindBy(xpath = "//*[@class='button invoice__stopEditButton']")
    public WebElement invoiceStopEditButtonLink;

    @FindBy(xpath = "//a[@class='button invoice__dataInputSave']")
    public WebElement acceptChangesButton;

    @FindBy(xpath = "//*[@class='invoice__dataInputCancel']")
    public WebElement discardChangesButton;

    @FindBy(xpath = "//a[@class='button invoice__removeConfirmButton']")
    public WebElement acceptDeleteButton;

    @FindBy(xpath = "//*[@class='invoice__removeCancel']")
    public WebElement discardDeleteButton;

    private String deleteButtonXpath = "//*[@class='invoice__removeLink']";

    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String checkType, String elementName, String expectedValue) {
        commonActions.checkElementValue(checkType, elementName, expectedValue);
    }

    public void checkCardValue(String elementName, String expectedValue) {
        checkCardValue("", elementName, expectedValue);
    }

    public void shouldContainsText(String elementName, String expectedValue) {
        WebElement element = items.get(elementName).getWebElement();
        commonPage.shouldContainsText(elementName, element, expectedValue);
    }

    public void checkCardValue(String checkType, ExamplesTable checkValuesTable) {
        commonActions.checkElementValue(checkType, checkValuesTable);
    }

    public void checkCardValue(ExamplesTable checkValuesTable) {
        checkCardValue("", checkValuesTable);
    }

    public void editButtonClick() {
        $(editButtonLink).click();
    }

    public void goToTheaAdditionOfProductsLinkClick() {
        findBy("//span[@class='button button_color_blue']/input").click();
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
        items.get(elementName).getWebElement().click();
    }

    public void acceptChangesButtonClick() {
        $(acceptChangesButton).click();
        WaitUntilEditFieldBecomeNotVisible();
    }

    public void discardChangesButtonClick() {
        $(discardChangesButton).click();
    }

    public void acceptDeleteButtonClick() {
        $(acceptDeleteButton).click();
        WaitUntilEditFieldBecomeNotVisible();
    }

    public void discardDeleteButtonClick() {
        $(discardDeleteButton).click();
    }

    public void invoiceStopEditButtonClick() {
        invoiceStopEditButtonLink.click();
    }

    public void invoiceStopEditlinkClick() {
        invoiceStopEditLink.click();
    }

    public void childrenElementClick(String elementName, String elementClassName) {
        commonViewInterface.childrenItemClickByClassName(elementName, elementClassName);
    }

    public void childrenItemNavigateAndClickByFindByLocator(String elementName) {
        commonViewInterface.childrenItemNavigateAndClickByFindByLocator(elementName, By.xpath(deleteButtonXpath));
    }

    public void tryTochildrenItemNavigateAndClickByFindByLocator(String elementName) {
        shouldNotBeVisible(By.xpath(deleteButtonXpath));
    }

    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        By findBy = items.get(parentElementName).getFindBy();
        commonViewInterface.childrentItemClickByFindByLocator(elementName, findBy);
    }

    public void addNewInvoiceProductButtonClick() {
        findBy("//span[@class='button button_color_blue invoice__addMoreProduct']/input").click();
        waiter.waitUntilIsNotPresent(By.xpath("//span[@class='button button_color_blue invoice__addMoreProduct preloader']"));
    }

    public void checkItemIsNotPresent(String elementName) {
        commonViewInterface.itemCheckIsNotPresent(elementName);
    }

    public void WaitUntilEditFieldBecomeNotVisible() {
        String xpath = "//*[@class='invoice__dataInput']";
        waiter.waitUntilIsNotPresent(By.xpath(xpath));
    }
}
