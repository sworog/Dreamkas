package ru.dreamkas.collection.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.helper.DateTimeHelper;

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
        String saleDateFromMap = ApiStorage.getCustomVariableStorage().getSalesMap().get(saleDate);
        String convertedSaleDate = DateTimeHelper.getExpectedSaleDate(saleDateFromMap, "EEEE, dd MMMM HH:mm");
        super.clickByLocator(locator.replace(saleDate, convertedSaleDate));
    }
}
