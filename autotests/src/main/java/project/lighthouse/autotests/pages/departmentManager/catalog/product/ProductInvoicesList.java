package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import junit.framework.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.product.ProductInvoiceListObject;
import project.lighthouse.autotests.objects.product.ProductInvoiceListObjectsList;

import java.util.List;

public class ProductInvoicesList extends CommonPageObject {

    public ProductInvoicesList(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("date", new NonType(this, "date"));
        items.put("number", new NonType(this, "number"));
        items.put("price", new NonType(this, "price"));
    }

    private List<WebElement> getProductInvoicesListWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@name='invoice']"));
    }

    public ProductInvoiceListObjectsList getProductInvoiceListObjects() {
        ProductInvoiceListObjectsList productInvoiceListObjects = new ProductInvoiceListObjectsList();
        for (WebElement element : getProductInvoicesListWebElements()) {
            ProductInvoiceListObject productInvoiceListObject = new ProductInvoiceListObject(element);
            productInvoiceListObjects.add(productInvoiceListObject);
        }
        return productInvoiceListObjects;
    }

    public void invoiceSkuClick(String sku) {
        //TODO fix xpath
        By by = By.xpath(String.format("//table//tr[@invoice-sku='%s']", sku));
        findVisibleElement(by).click();
    }

    public void checkInvoiceData(String date, String quantity, String price, String totalPrice) {
        Assert.assertEquals(findModelFieldContaining("productInvoice", "acceptanceDateFormatted", date).getText(), date);
        Assert.assertEquals(findModelFieldContaining("productInvoice", "quantity", quantity).getText(), quantity);
        Assert.assertEquals(findModelFieldContaining("productInvoice", "priceFormatted", price).getText(), price);
        Assert.assertEquals(findModelFieldContaining("productInvoice", "totalPriceFormatted", totalPrice).getText(), totalPrice);
    }
}
