package project.lighthouse.autotests.demo;

import project.lighthouse.autotests.thucydides.TeamCityStepListener;

import javax.swing.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class ThucydidesControl extends JFrame {

    JLabel jLabel;

    public ThucydidesControl() {
        initUI();
    }

    private void initUI() {
        JPanel panel = new JPanel();
        getContentPane().add(panel);
        panel.setLayout(null);

        jLabel = new JLabel();
        jLabel.setBounds(20, 15, 500, 20);
        jLabel.updateUI();
        panel.add(jLabel);

        final JButton controlButton = new JButton("Pause");
        controlButton.setBounds(20, 40, 100, 50);

        controlButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent event) {
                switch (controlButton.getText()) {
                    case "Pause":
                        TeamCityStepListener.isPaused = true;
                        controlButton.setText("Start");
                        break;
                    case "Start":
                        TeamCityStepListener.isPaused = false;
                        controlButton.setText("Pause");
                        break;
                }
            }
        });

        panel.add(controlButton);

        setTitle("Thucydides");
        setSize(600, 150);
        setLocationRelativeTo(null);
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        setVisible(true);
    }

    public void setJLabelText(String text) {
        jLabel.setText(text);
    }

    public void updateJLabelUI() {
        jLabel.updateUI();
    }
}
