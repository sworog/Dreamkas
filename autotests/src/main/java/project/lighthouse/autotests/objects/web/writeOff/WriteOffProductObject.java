package project.lighthouse.autotests.objects.web.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;
import project.lighthouse.autotests.objects.web.abstractObjects.ObjectProperty;

import java.util.Map;

public class WriteOffProductObject extends AbstractObjectNode {

    private ObjectProperty name;
    private ObjectProperty sku;
    private ObjectProperty barcode;
    private ObjectProperty quantity;
    private ObjectProperty units;
    private ObjectProperty price;
    private ObjectProperty productCause;

    public WriteOffProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = setObjectProperty("productName", By.name("productName"));
        sku = setObjectProperty("productSku", By.name("productSku"));
        barcode = setObjectProperty("productBarcode", By.name("productBarcode"));
        quantity = setObjectProperty("productAmount", By.name("productAmount"));
        units = setObjectProperty("productUnits", By.name("productUnits"));
        price = setObjectProperty("productPrice", By.name("productPrice"));
        productCause = setObjectProperty("productCause", By.name("productCause"));
    }

    public Boolean rowIsEqual(Map<String, String> row) {
        return name.getText().equals(row.get("productName")) &&
                sku.getText().equals(row.get("productSku")) &&
                barcode.getText().equals(row.get("productBarcode")) &&
                quantity.getText().equals(row.get("productAmount")) &&
                units.getText().equals(row.get("productUnits")) &&
                price.getText().equals(row.get("productPrice")) &&
                productCause.getText().equals(row.get("productCause"));
    }

    @Override
    public String getObjectLocator() {
        return sku.getText();
    }
}
