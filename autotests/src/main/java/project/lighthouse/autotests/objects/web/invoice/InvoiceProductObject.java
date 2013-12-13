package project.lighthouse.autotests.objects.web.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.ObjectProperty;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class InvoiceProductObject extends AbstractObject implements ObjectLocatable, ResultComparable {

    private ObjectProperty name;
    private ObjectProperty sku;
    private ObjectProperty barcode;
    private ObjectProperty quantity;
    private ObjectProperty units;
    private ObjectProperty price;
    private ObjectProperty sum;

    private ObjectProperty vatSum;
    private ObjectProperty vat;

    public InvoiceProductObject(WebElement element) {
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
        sum = setObjectProperty("productSum", By.name("productSum"));
        vatSum = setObjectProperty("vatSum", By.xpath(".//*[@model-attribute='productTotalAmountVATFormatted']"));
        vat = setObjectProperty("vat", By.xpath(".//*[@model-attribute='product.vat']"));
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("productSku", sku.getText(), row.get("productSku"))
                .compare("productName", name.getText(), row.get("productName"))
                .compare("productBarcode", barcode.getText(), row.get("productBarcode"))
                .compare("productAmount", quantity.getText(), row.get("productAmount"))
                .compare("productUnits", units.getText(), row.get("productUnits"))
                .compare("productPrice", price.getText(), row.get("productPrice"))
                .compare("productSum", sum.getText(), row.get("productSum"))
                .compare("vatSum", vatSum.getText(), row.get("vatSum"))
                .compare("vat", vat.getText(), row.get("vat"));
    }

    @Override
    public String getObjectLocator() {
        return sku.getText();
    }
}
