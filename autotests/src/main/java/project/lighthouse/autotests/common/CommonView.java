package project.lighthouse.autotests.common;


import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.amount.AmountListPage;
import project.lighthouse.autotests.pages.invoice.InvoiceBrowsing;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

import java.util.Map;

public class CommonView extends CommonPageObject implements CommonViewInterface {

    String listPageName;
    String listPageSkuName;
    private static final String XPATH_PATTERN = "//*[@name='%s']//*[@name='%s' and normalize-space(text())='%s']/..";

    public CommonView(WebDriver driver, String listPageName, String listPageSkuName) {
        super(driver);
        this.listPageName = listPageName;
        this.listPageSkuName = listPageSkuName;
    }

    public String getCorrectXpathPattern() {
        switch (listPageName) {
            case InvoiceBrowsing.ITEM_NAME:
            case AmountListPage.ITEM_NAME:
            case ProductListPage.ITEM_NAME:
            case InvoiceListPage.ITEM_NAME:
                return XPATH_PATTERN + "/..";
            default:
                return XPATH_PATTERN;
        }
    }

    public String getXpath(String value) {
        return String.format(getCorrectXpathPattern(), listPageName, listPageSkuName, value);
    }

    public WebElement getWebElementItem(String value) {
        String getXpath = getXpath(value);
        return waiter.getVisibleWebElement(By.xpath(getXpath));
    }

    public void itemCheck(String value) {
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
        }
        try {
            commonActions.elementShouldBeVisible(value, this);
        } catch (AssertionError e) {
            String errorMessage = String.format("The element with value '%s' is not present\nException message: %s", value, e.getMessage());
            throw new AssertionError(errorMessage);
        }
    }

    public void itemCheckIsNotPresent(String value) {
        try {
            WebElement listItem = getWebElementItem(value);
            $(listItem).shouldNotBeVisible();
        } catch (Exception e) {
        }
    }

    public void itemClick(String value) {
        WebElement listItem = getWebElementItem(value);
        listItem.click();
    }

    public void childrenItemClickByClassName(String elementName, String elementClassName) {
        getWebElementItem(elementName).findElement(By.name(elementClassName)).click();
    }

    public void childrentItemClickByFindByLocator(String elementName, By by) {
        getWebElementItem(elementName).findElement(by).click();
    }

    public void childrenItemNavigateAndClickByFindByLocator(String elementName, By by) {
        withAction().moveToElement(getWebElementItem(elementName)).click(findElement(by)).build().perform();
    }

    public void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue) {
        itemCheck(value);
        WebElement listItem = getWebElementItem(value).findElement(By.name(elementName));
        commonPage.shouldContainsText(elementName, listItem, expectedValue);
    }

    public void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkListItemWithSkuHasExpectedValue(value, elementName, expectedValue);
        }
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, By findBy, String expectedValue) {
        itemCheck(value);
        WebElement listItem = getWebElementItem(value).findElement(findBy);
        commonPage.shouldContainsText(elementName, listItem, expectedValue);
    }

    @Override
    public void createElements() {
    }
}
