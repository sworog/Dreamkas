package project.lighthouse.autotests.pages;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

public class CommonPage extends PageObject {

    public CommonPage(WebDriver driver) {
        super(driver);
    }

    public void isRequiredPageOpen(String pageObjectName){
        String defaultUrl = GetPageObjectDefaultUrl(pageObjectName).substring(50, 64);
        String actualUrl = getDriver().getCurrentUrl();
        if(!actualUrl.contains(defaultUrl)){
            String errorMessage = String.format("The %s is not open!\nActual url: %s\nExpected url: %s", pageObjectName, actualUrl, defaultUrl);
            throw new AssertionError(errorMessage);
        }
    }

    public String GetPageObjectDefaultUrl(String pageObjectName){
        switch (pageObjectName){
            case "ProductListPage":
                return ProductListPage.class.getAnnotations()[0].toString();
            case "InvoiceListPage":
                return InvoiceListPage.class.getAnnotations()[0].toString();

            default:
                return String.valueOf(new AssertionError("No such value!"));
        }
    }

    public String GenerateTestData(int n){
        String str = "a";
        String testData = new String(new char[n]).replace("\0", str);
        StringBuilder formattedData = new StringBuilder(testData);
        for(int i = 0; i < formattedData.length(); i++){
            if (i%38 == 1){
                formattedData.setCharAt(i, ' ');
            }
        }
        return formattedData.toString();
    }
}
