package project.lighthouse.autotests.objects.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.product.abstractObjects.AbstractProductObjectList;

import java.util.Map;

import static junit.framework.Assert.assertEquals;

public class ProductInvoiceListObject extends AbstractProductObjectList {

    private String date;
    private String number;
    private String price;

    public ProductInvoiceListObject(WebElement element) {
        super(element);
        setProperties();
    }

    @Override
    public String getValues() {
        return String.format("%s, %s, %s", date, number, price);
    }

    public void setProperties() {
        date = element.findElement(By.xpath(".//")).getText();
        number = element.findElement(By.xpath(".//")).getText();
        price = element.findElement(By.xpath(".//")).getText();
    }

    public void compareWithExamplesTableRow(Map<String, String> row) {
        assertEquals(date, row.get("date"));
        assertEquals(number, row.get("number"));
        assertEquals(price, row.get("price"));
    }

    public Boolean rowIsEqual(Map<String, String> row) {
        return date.equals(row.get("date")) &&
                number.equals(row.get("number")) &&
                price.equals(row.get("price"));
    }
}
