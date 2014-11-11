package ru.dreamkas.pageObjects;

import net.thucydides.core.annotations.findby.By;
import net.thucydides.core.annotations.findby.FindBy;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import ru.dreamkas.pageObjects.elements.Button;
import ru.dreamkas.pageObjects.elements.Drawer;
import ru.dreamkas.pageObjects.elements.Input;
import ru.dreamkas.pageObjects.elements.Spinner;
import ru.dreamkas.pageObjects.elements.TextView;
import ru.dreamkas.pageObjects.elements.interfaces.Elementable;

public class PosPage extends CommonPageObject {

    @FindBy(id = "android:id/home")
    private WebElement drawerIcon;

    @FindBy(id = "ru.dreamkas.pos.debug:id/lstDrawer")
    private WebElement lstDrawer;

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

    public PosPage(WebDriver driver){
        super(driver);
        putElementable("Очистить чек", new Button(this, "btnReceiptClear"));
        putElementable("Перейти к кассе", new Button(this, "btnSaveStoreSettings"));
        putElementable("Магазины", new Spinner(this, "spStores"));
        putElementable("Заголовок окна", new TextView(this, "android:id/", "action_bar_title" ));
        putElementable("Боковое меню", new Drawer(this, "android:id/", "home" ));
    }

    public String getStore() {
        return lblStore.getText();
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

    public void tapOnReceiptListItemWithTitle(String title) {
        List<WebElement> items = lvReceipt.findElements(By.className("android.widget.TextView"));
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
    }

    public String getReceiptTotalButtonLabel() {
        return registerReceiptButton.getText();
    }



    public String getEditReceiptModalTitle() {
        WebElement title = getAppiumDriver().findElement(By.id("ru.dreamkas.pos.debug:id/lblTotal"));
        return title.getText();
    }

    public String getEditReceiptModalProductName() {
        WebElement productName = getAppiumDriver().findElement(By.id("ru.dreamkas.pos.debug:id/lblProductName"));
        return productName.getText();
    }

    public String getEditReceiptModalSellingPrice() {
        WebElement sellingPrice = getAppiumDriver().findElement(By.id("ru.dreamkas.pos.debug:id/txtSellingPrice"));
        return sellingPrice.getText();
    }

    public String getEditReceiptModalQuantity() {
        WebElement quantity = getAppiumDriver().findElement(By.id("ru.dreamkas.pos.debug:id/txtValue"));
        return quantity.getText();
    }
}
