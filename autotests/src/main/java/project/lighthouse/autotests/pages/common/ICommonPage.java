package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.ICommonPageInterface;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;
import sun.reflect.generics.reflectiveObjects.NotImplementedException;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Map;

public class ICommonPage extends PageObject implements ICommonPageInterface {

    public static final String ERROR_MESSAGE = "No such option for '%s'";

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

    public static String GetTodayDate(){
        String pattern = "dd.MM.yyyy HH:mm";
        return new SimpleDateFormat(pattern).format(new Date());
    }

    public String getInputedText(String inputText){
        switch (inputText){
            case "todayDate":
                return GetTodayDate();
            default:
                return inputText;
        }
    }

    public void CheckErrorMessages(ExamplesTable errorMessageTable){
        for (Map<String, String> row : errorMessageTable.getRows()){
            String expectedErrorMessage = row.get("error message");
            String xpath = String.format("//*[contains(@lh_field_error,'%s')]", expectedErrorMessage);
            if(!IsPresent(xpath)){
                String errorXpath = "//*[@lh_field_error]";
                String errorMessage;
                if(IsPresent(errorXpath)){
                    List<WebElementFacade> webElementList = findAll(By.xpath(errorXpath));
                    StringBuilder builder = new StringBuilder("Another validation errors are present:");
                    for (WebElementFacade element : webElementList){
                        builder.append(element.getAttribute("lh_field_error"));
                    }
                    errorMessage = builder.toString();
                }
                else{
                    errorMessage = "There are no error field validation messages on the page!";
                }
                throw new AssertionError(errorMessage);
            }
        }
    }

    public void CheckNoErrorMessages(){
        String xpath = "//*[@lh_field_error]";
        if(IsPresent(xpath)){
            String errorMessage = "There are error field validation messages on the page!";
            throw new AssertionError(errorMessage);
        }
    }

    public void SetValue(CommonItem item, String value){
        switch (item.GetType()) {
            case date:
                InputDate(item.GetWebElement(), value);
                break;
            case input:
            case textarea:
                Input(item.GetWebElement(), value);
                break;
            case checkbox:
                SelectByValue(item.GetWebElement(), value);
                break;
            case autocomplete:
                AutoComplete(item.GetWebElement(), value);
                break;
            default:
                String errorMessage = String.format(ERROR_MESSAGE, item.GetType());
                throw new AssertionError(errorMessage);
        }
    }

    public void Input(WebElement element, String value){
        String inputText = getInputedText(value);
        $(element).type(inputText);
    }

    public void SelectByValue(WebElement element, String value){
        $(element).selectByValue(value);
    }

    public void InputDate(WebElement element, String value){
        if(value.startsWith("!")){
            Input(element, value.substring(1));
        }
        else{
            if(value.equals("todayDate")){
                String todayDate = GetTodayDate();
                DatePickerInput(todayDate);
            }
            else{
                DatePickerInput(value);
            }
        }
    }

    public void DatePickerInput(String datePattern){
        throw new NotImplementedException();
    }

    public void AutoComplete(WebElement element, String value){
        throw new NotImplementedException();
    }
}
