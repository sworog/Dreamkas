package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonView;

import java.util.Map;

public class InvoiceBrowsing extends InvoiceCreatePage {

    public static final String ITEM_NAME = "invoiceProduct";
    private static final String ITEM_SKU_NAME = "productSku";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    @FindBy(xpath = "//*[@class='saveInvoiceAndAddProduct']")
    private WebElement goToTheaAdditionOfProductsLink;

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


    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String checkType, String elementName, String expectedValue) {
        WebElement element;
        if (checkType.isEmpty()) {
            element = items.get(elementName).getWebElement();
        } else {
            WebElement parent = items.get(checkType).getWebElement();
            element = items.get(elementName).getWebElement(parent);
        }
        commonPage.shouldContainsText(elementName, element, expectedValue);
    }

    public void checkCardValue(String elementName, String expectedValue) {
        checkCardValue("", elementName, expectedValue);
    }

    public void shouldContainsText(String elementName, String expectedValue) {
        WebElement element = items.get(elementName).getWebElement();
        commonPage.shouldContainsText(elementName, element, expectedValue);
    }

    public void checkCardValue(String checkType, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkCardValue(checkType, elementName, expectedValue);
        }
    }

    public void checkCardValue(ExamplesTable checkValuesTable) {
        checkCardValue("", checkValuesTable);
    }

    public void editButtonClick() {
        $(editButtonLink).click();
    }

    public void goToTheaAdditionOfProductsLinkClick() {
        $(goToTheaAdditionOfProductsLink).click();
    }

    public void addOneMoreProductLinkClick() {
        $(addOneMoreProductLink).click();
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
        try {
            Thread.sleep(100);
        } catch (InterruptedException e) {
            e.printStackTrace();  //To change body of catch statement use File | Settings | File Templates.
        }
    }

    public void discardChangesButtonClick() {
        $(discardChangesButton).click();
    }

    public void acceptDeleteButtonClick() {
        $(acceptDeleteButton).click();
        try {
            Thread.sleep(100);
        } catch (InterruptedException e) {
            e.printStackTrace();  //To change body of catch statement use File | Settings | File Templates.
        }
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
        String deleteButtonXpath = "//*[@class='invoice__removeLink']";
        commonViewInterface.childrenItemNavigateAndClickByFindByLocator(elementName, By.xpath(deleteButtonXpath));
    }

    public void tryTochildrenItemNavigateAndClickByFindByLocator(String elementName) {
        try {
            childrenItemNavigateAndClickByFindByLocator(elementName);
            String errorMessage = String.format("the user can delete the product with sku '%s' in not edit mode", elementName);
            throw new AssertionError(errorMessage);
        } catch (Exception e) {
        }
    }

    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        By findBy = items.get(parentElementName).getFindBy();
        commonViewInterface.childrentItemClickByFindByLocator(elementName, findBy);
    }

    public void addNewInvoiceProductButtonClick() {
        findBy("//span[@class='button button_color_blue invoice__addMoreProduct']/input").click();
    }

    public void checkItemIsNotPresent(String elementName) {
        commonViewInterface.itemCheckIsNotPresent(elementName);
    }
}
