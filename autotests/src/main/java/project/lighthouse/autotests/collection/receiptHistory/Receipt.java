package project.lighthouse.autotests.collection.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResults;

import java.util.Map;

public class Receipt extends AbstractObject implements ResultComparable {

    private String name;
    private String quantity;
    private String price;

    public Receipt(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        price = getElement().findElement(By.name("price")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("price", price, row.get("price"));
    }
}
