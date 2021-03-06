$(function () {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		$('#calendar-holder').fullCalendar({
			header: {
				left: 'prev,today,next',
				center: 'title',
				right: 'month,agendaWeek'
			},
			lazyFetching:true,
            timeFormat: {
                    // for agendaWeek and agendaDay
                    agenda: 'H:mm{ - H:mm}', // 5:00 - 6:30

                    // for all other views
                    '': 'H:mm'            // 7p
            },
			eventSources: [
                    {
                        url: Routing.generate('fullcalendar_loader', {'filtre':"categorie_reunions"}),
						type: 'POST',
						// A way to add custom filters to your event listeners
                        data: {
                        
                        },
                        error: function() {
                           //alert('There was an error while fetching Google Calendar!');
                        }
                    }
			],

			// Custom Settings
			firstDay: 1,
			weekMode: 'variable',
			allDaySlot: false,
			axisFormat: 'H:mm',
			minTime: 8,
			maxTime: 22,
			defaultView: 'agendaWeek',
			buttonText:
			{
			    today: 'Aujourd\'hui',
			    month: 'Mois',
			    week: 'Semaine'
			},
			columnFormat:
			{
		        week: 'ddd. d MMM.'
			},
			titleFormat:
			{
			    week: "'Semaine du' d[ MMMM]{ 'au' d MMMM}"
			},
			monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthNamesShort: ['Jan', 'Fev', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'],
			dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
		});
});
