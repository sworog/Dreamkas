package project.lighthouse.autotests.pages.common;


import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.invoice.InvoiceBrowsing;

import java.util.Map;

public class CommonView extends CommonPageObject implements CommonViewInterface {

    String listPageName;
    String listPageSkuName;
    private static final String XPATH_PATTERN = "//*[@name='%s']//*[@name='%s' and normalize-space(text())='%s']/..";
    CommonPageInterface commonPageInterface = new CommonPage(getDriver());

    public CommonView(WebDriver driver, String listPageName, String listPageSkuName) {
        super(driver);
        this.listPageName = listPageName;
        this.listPageSkuName = listPageSkuName;
    }

    public String getCorrectXpathPattern() {
        switch (listPageName) {
            case InvoiceBrowsing.ITEM_NAME:
                return XPATH_PATTERN + "/..";
            default:
                return XPATH_PATTERN;
        }
    }

    public String getXpath(String value) {
        return String.format(getCorrectXpathPattern(), listPageName, listPageSkuName, value);
    }

    public WebElementFacade getWebElementFacadeItem(String value) {
        String getXpath = getXpath(value);
        return findBy(getXpath);
    }

    public WebElement getWebElementItem(String value) {
        String getXpath = getXpath(value);
        return getDriver().findElement(By.xpath(getXpath));
    }

    public void itemCheck(String value) {
        WebElementFacade listItem = getWebElementFacadeItem(value);
        listItem.shouldBePresent();
    }

    public void itemCheckIsNotPresent(String value) {
        try {
            WebElementFacade listItem = getWebElementFacadeItem(value);
            listItem.shouldNotBeVisible();
        } catch (Exception e) {
        }
    }

    public void itemClick(String value) {
        WebElementFacade listItem = getWebElementFacadeItem(value);
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
        commonPageInterface.shouldContainsText(elementName, listItem, expectedValue);
    }

    public void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkListItemWithSkuHasExpectedValue(value, elementName, expectedValue);
        }
    }

    @Override
    public void createElements() {
        //To change body of implemented methods use File | Settings | File Templates.
    }
}
