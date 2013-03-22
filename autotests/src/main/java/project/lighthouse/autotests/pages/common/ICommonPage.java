package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.Alert;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.ICommonPageInterface;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

public class ICommonPage extends PageObject implements ICommonPageInterface {

    public ICommonPage(WebDriver driver) {
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
            if (i%26 == 1){
                formattedData.setCharAt(i, ' ');
            }
        }
        return formattedData.toString();
    }

    public boolean IsPresent(String xpath){
        try {
            return findBy(xpath).isPresent();
        }
        catch (Exception e){
            return false;
        }
    }

    public void CheckCreateAlertSuccess(String name){
        boolean isAlertPresent;
        Alert alert = null;
        try {
            alert = getAlert();
            isAlertPresent = true;
        }
        catch (Exception e){
            isAlertPresent = false;
        }
        if(isAlertPresent){
            String errorAlertMessage = "Ошибка";
            String alertText = alert.getText();
            if(alertText.contains(errorAlertMessage)){
                String errorMessage = String.format("Can't create new '%s'. Error alert is present. Alert text: %s", name, alertText);
                throw new AssertionError(errorMessage);
            }
        }
    }

    public void CheckFieldLength(String elementName, int fieldLength, WebElement element) {
        int length;
        switch (element.getTagName()){
            case "input":
                length = $(element).getTextValue().length();
                break;
            case "textarea":
                length = $(element).getValue().length();
                break;
            default:
                length = $(element).getText().length();
                break;
        }
        if(length != fieldLength){
            String errorMessage = String.format("The '%s' field doesn't contains '%s' symbols. It actually contains '%s' symbols.", elementName, fieldLength, length);
            throw new AssertionError(errorMessage);
        }
    }
}
