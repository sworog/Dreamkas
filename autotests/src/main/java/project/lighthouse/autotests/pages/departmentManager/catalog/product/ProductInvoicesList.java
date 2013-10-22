package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import junit.framework.Assert;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.ProductInvoiceListObject;
import project.lighthouse.autotests.objects.ProductInvoiceListObjectsList;

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
        WebElementFacade table = find(By.xpath("//table"));
        return table.findElements(By.xpath("//*[@name='invoice']"));
//        return waiter.getPresentWebElements(By.xpath("//*[@name='invoice']"));
    }

    public ProductInvoiceListObjectsList getProductInvoiceListObjects() {
        ProductInvoiceListObjectsList productInvoiceListObjects = new ProductInvoiceListObjectsList();
        for (WebElement element : getProductInvoicesListWebElements()) {
            ProductInvoiceListObject productInvoiceListObject = new ProductInvoiceListObject(getDriver(), element);
            productInvoiceListObjects.add(productInvoiceListObject);
        }
        return productInvoiceListObjects;
    }

    public void invoiceSkuClick(String sku) {
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
