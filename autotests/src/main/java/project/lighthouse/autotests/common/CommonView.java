package project.lighthouse.autotests.common;


import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.departmentManager.invoice.deprecated.InvoiceBrowsing;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffListPage;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffPage;

import java.util.Map;

import static junit.framework.Assert.fail;

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
            case WriteOffPage.ITEM_NAME:
            case WriteOffListPage.ITEM_NAME:
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
        return findVisibleElement(By.xpath(getXpath));
    }

    public void itemCheck(String value) {
        // TODO removed thread sleep method
        try {
            Thread.sleep(1000);
        } catch (InterruptedException ignored) {
        }
        try {
            elementShouldBeVisible(value, this);
        } catch (AssertionError e) {
            fail(
                    String.format("The element with value '%s' is not present\nException message: %s", value, e.getMessage())
            );
        }
    }

    public void itemCheckIsNotPresent(String value) {
        try {
            WebElement listItem = getWebElementItem(value);
            $(listItem).shouldNotBeVisible();
        } catch (Exception ignored) {
        }
    }

    public void itemClick(String value) {
        WebElement listItem = getWebElementItem(value);
        String scrollScript = String.format("window.scrollTo(%s, %s)", listItem.getLocation().getX(), listItem.getLocation().getY() - 30);
        evaluateJavascript(scrollScript);
        listItem.click();
    }

    public void childrenItemClickByClassName(String elementName, String elementClassName) {
        getWebElementItem(elementName).findElement(By.name(elementClassName)).click();
    }

    public void childrenItemClickByFindByLocator(String elementName, By by) {
        getWebElementItem(elementName).findElement(by).click();
    }

    public void childrenItemNavigateAndClickByFindByLocator(String elementName, By by) {
        withAction().moveToElement(getWebElementItem(elementName)).click(findElement(by)).build().perform();
    }

    public void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue) {
        itemCheck(value);
        WebElement listItem = getWebElementItem(value).findElement(By.name(elementName));
        getCommonActions().shouldContainsText(elementName, listItem, expectedValue);
    }

    public void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("value");
            checkListItemWithSkuHasExpectedValue(value, elementName, expectedValue);
        }
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, By findBy, String expectedValue) {
        itemCheck(value);
        WebElement listItem = getWebElementItem(value).findElement(findBy);
        getCommonActions().shouldContainsText(elementName, listItem, expectedValue);
    }

    @Override
    public void createElements() {
    }
}
