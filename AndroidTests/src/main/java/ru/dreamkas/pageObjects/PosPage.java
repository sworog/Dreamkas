package ru.dreamkas.pageObjects;

import net.thucydides.core.annotations.findby.By;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.List;

public class PosPage extends CommonPageObject {

    @FindBy(id = "android:id/home")
    private WebElement drawerIcon;

    @FindBy(id = "android:id/action_bar_title")
    private WebElement actionBarTitle;

    @FindBy(id = "ru.dreamkas.pos.debug:id/lstDrawer")
    private WebElement lstDrawer;

    @FindBy(id = "ru.dreamkas.pos.debug:id/btnSaveStoreSettings")
    private WebElement btnSaveStoreSettings;

    @FindBy(id = "ru.dreamkas.pos.debug:id/spStores")
    private WebElement spStores;

    @FindBy(id = "android:id/text1")
    private List<WebElement> spinnerElements;

    @FindBy(id = "ru.dreamkas.pos.debug:id/lblStore")
    WebElement lblStore;

    @FindBy(id = "ru.dreamkas.pos.debug:id/txtProductSearchQuery")
    private WebElement txtProductSearchQuery;

    @FindBy(id = "ru.dreamkas.pos.debug:id/lvProductsSearchResult")
    private WebElement lvProductsSearchResult;

    @FindBy(id = "ru.dreamkas.pos.debug:id/lvReceipt")
    private WebElement lvReceipt;


    @FindBy(id = "ru.dreamkas.pos.debug:id/lblSearchResultEmpty")
    private WebElement searchResultEmptyLabel;

    @FindBy(id = "ru.dreamkas.pos.debug:id/lblReceiptEmpty")
    private WebElement receiptEmptyLabel;

    @FindBy(id = "ru.dreamkas.pos.debug:id/btnRegisterReceipt")
    private WebElement registerReceiptButton;

    public String getActionBarTitle() {
        return actionBarTitle.getText();
    }

    public void chooseSpinnerItemWithValue(String value) {
        spStores.click();
        clickOnElementWithText(spinnerElements, value);
    }

    public void clickOnSaveStoreSettings() {
        btnSaveStoreSettings.click();
    }

    public String getStore() {
        return lblStore.getText();
    }

    public void openDrawerAndClickOnDrawerOption(String menuOption) {
        drawerIcon.click();
        clickOnElementWithText(getAppiumDriver().findElements(By.xpath("//android.widget.TextView")), menuOption);
    }

    public void inputProductSearchQuery(String productSearchQuery) {
        txtProductSearchQuery.sendKeys(productSearchQuery);
    }

    public Integer getSearchProductResultCount() {
        List<WebElement> items = lvProductsSearchResult.findElements(By.className("android.widget.TextView"));
        return items.size();
    }

    public List<String> getSearchProductResult() {
        return getListViewItemsTitles(lvProductsSearchResult, "android.widget.TextView");
        /*List<WebElement> items = lvProductsSearchResult.findElements(By.className("android.widget.TextView"));
        List<String> strs = new ArrayList<String>();
        for (WebElement webElement : items) {
            strs.add(webElement.getText());
        }
        return strs;*/
    }

    public String getSearchResultEmptyLabel() {
        return searchResultEmptyLabel.getText();
    }

    public String getReceiptEmptyLabel() {
        return receiptEmptyLabel.getText();
    }

    public void tapOnSearchListItemWithTitle(String title) {
        List<WebElement> items = lvProductsSearchResult.findElements(By.className("android.widget.TextView"));
        clickOnElementWithText(items, title);
    }

    public Integer getReceiptItemsCount() {
        List<WebElement> items = lvReceipt.findElements(By.className("android.widget.LinearLayout"));
        return items.size();
    }

    public List<List<String>> getReceiptItems() {
        List<WebElement> items = lvReceipt.findElements(By.className("android.widget.LinearLayout"));
        List<List<String>> rows = new ArrayList<List<String>>();
        for (WebElement webElement : items) {
            List<String> cells = new ArrayList<String>();
            List<WebElement> elements = webElement.findElements(By.className("android.widget.TextView"));
            for(WebElement element : elements){
                cells.add(element.getText());
            }
            rows.add(cells);
        }
        return rows;
        //return getListViewItemsTitles(lvReceipt, "android.widget.TextView");
    }

    public String getReceiptTotalButtonLabel() {
        return registerReceiptButton.getText();
    }

    public void clickOnClearReceiptButton() {
        WebElement btnClearReceiptButton = getAppiumDriver().findElement(By.id("ru.dreamkas.pos.debug:id/btnReceiptClear"));
        btnClearReceiptButton.click();
    }

    public String getReceiptClearButtonLabel() {
        WebElement btnClearReceiptButton = getAppiumDriver().findElement(By.id("ru.dreamkas.pos.debug:id/btnReceiptClear"));
        return btnClearReceiptButton.getText();
    }
}
