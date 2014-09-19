package project.lighthouse.autotests.collection.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.storage.Storage;

public class HistoryReceiptCollection extends AbstractObjectCollection {

    public HistoryReceiptCollection(WebDriver webDriver) {
        super(webDriver, By.name("receipt"));
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new HistoryReceipt(element);
    }

    @Override
    public void clickByLocator(String locator) {
        String saleDate = locator.split(".*датой ")[1];
        String saleDateFromMap = Storage.getCustomVariableStorage().getSalesMap().get(saleDate);
        String convertedSaleDate = DateTimeHelper.getExpectedSaleDate(saleDateFromMap, "EEEE, dd MMMM HH:mm");
        super.clickByLocator(locator.replace(saleDate, convertedSaleDate));
    }
}
