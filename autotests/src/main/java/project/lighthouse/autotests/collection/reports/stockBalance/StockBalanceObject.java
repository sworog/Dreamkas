package project.lighthouse.autotests.collection.reports.stockBalance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResults;

import java.util.Map;

public class StockBalanceObject extends AbstractObject implements ResultComparable {

    private String name;
    private String barcode;
    private String inventoryDays;
    private String averageDailySales;
    private String inventory;

    public StockBalanceObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        barcode = getElement().findElement(By.name("barcode")).getText();
        inventoryDays = getElement().findElement(By.name("inventoryDays")).getText();
        averageDailySales = getElement().findElement(By.name("averageDailySales")).getText();
        inventory = getElement().findElement(By.name("inventory")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("название"))
                .compare("barcode", barcode, row.get("штрихкод"))
                .compare("inventoryDays", inventoryDays, row.get("запас"))
                .compare("averageDailySales", averageDailySales, row.get("расход"))
                .compare("inventory", inventory, row.get("остаток"));
    }
}
