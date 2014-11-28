package ru.dreamkas.pages.dialogs;

import ru.dreamkas.elements.Text;
import ru.dreamkas.pages.CommonPageObject;

public class SelectStoreDialog extends CommonPageObject {

    @Override
    public void createElements() {
        putElement("Заголовок", new Text(this, "Выбор магазина"));
    }
}
