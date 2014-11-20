package ru.dreamkas.pageObjects;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.pageObjects.elements.Button;
import ru.dreamkas.pageObjects.elements.Drawer;
import ru.dreamkas.pageObjects.elements.Input;
import ru.dreamkas.pageObjects.elements.ReceiptList;
import ru.dreamkas.pageObjects.elements.SearchResultListView;
import ru.dreamkas.pageObjects.elements.Spinner;
import ru.dreamkas.pageObjects.elements.TextView;

public class PosPage extends CommonPageObject {

    public PosPage(WebDriver driver){
        super(driver);
        putElementable("Очистить чек", new Button(this, "btnReceiptClear"));
        putElementable("Перейти к кассе", new Button(this, "btnSaveStoreSettings"));
        putElementable("Продать", new Button(this, "btnRegisterReceipt"));

        putElementable("Поиск товаров", new Input(this, "txtProductSearchQuery" ));

        putElementable("Заголовок окна", new TextView(this, "android:id/", "action_bar_title" ));
        putElementable("Сообщение о результатах поиска товаров", new TextView(this, "lblSearchResultEmpty" ));
        putElementable("Сообщение пустого чека", new TextView(this, "lblReceiptEmpty" ));
        putElementable("Магазин", new TextView(this, "lblStore" ));

        putElementable("Боковое меню", new Drawer(this, "android:id/", "home" ));
        putElementable("Магазины", new Spinner(this, "spStores"));
        putElementable("Результаты поиска товаров", new SearchResultListView(this, "lvProductsSearchResult" ));
        putElementable("Чек продажи", new ReceiptList(this, "lvReceipt" ));
    }
}
