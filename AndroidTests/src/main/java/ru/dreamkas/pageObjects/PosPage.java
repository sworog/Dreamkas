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

    @FindBy(id = "ru.dreamkas.pos.debug:id/lblSearchResultEmpty")
    private WebElement searchResultEmptyLabel;

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
        List<WebElement> items = lvProductsSearchResult.findElements(By.className("android.widget.TextView"));
        List<String> strs = new ArrayList<String>();
        for (WebElement webElement : items) {
            strs.add(webElement.getText());
        }
        return strs;
    }

    public String getSearchResultEmptyLabel() {
        return searchResultEmptyLabel.getText();
    }
}
